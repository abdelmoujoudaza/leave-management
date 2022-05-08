<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Models\Station;
use Livewire\Component;
use App\Models\Direction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

class UpdateUser extends Component
{
    public $currentRouteName;
    public $user;
    public $role;
    public $roles;
    public $password = '';
    public $collection;
    public $selectedItem;

    public function mount(User $user)
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->user = $user;
        $this->role = $this->user->roles()->first()->id;
        $this->collection   = ($this->currentRouteName == 'student.update') ? Station::all() : Direction::all();
        $this->selectedItem = ($this->currentRouteName == 'student.update')
            ? $this->user->station->id
            : $this->user->direction->id;
    }

    public function render()
    {
        return view('livewire.user.update-user');
    }

    public function rules()
    {
        return [
            'user.national_id' => 'required|string|max:15',
            'user.firstname'   => 'required|string|max:100',
            'user.lastname'    => 'required|string|max:100',
            'user.address'     => 'nullable|string|max:100',
            'user.birth'       => 'required|date',
            'user.gender'      => 'required|string|in:man,woman',
            'user.email'       => 'required|string|email|max:191|unique:users,email',
            'password'         => 'nullable|string|min:8|max:100',
            'role'             => 'required|exists:roles,id',
            'selectedItem'     => 'required|exists:' . (($this->currentRouteName == 'student.update') ? 'stations' : 'directions') . ',id',
        ];
    }

    public function updated($property, $value)
    {
        if ($property == 'user.email') {
            $this->validateOnly($property, [
                'user.email' => "required|string|email|max:191|unique:users,email,{$this->user->id}",
            ]);
        } else {
            $this->validateOnly($property);
        }
    }

    public function back()
    {
        return redirect()->route($this->currentRouteName == 'student.update' ? 'student.list' : 'driver.list');
    }

    public function submit()
    {
        $this->validate(
            Arr::prepend(
                $this->getRules(),
                "required|string|email|max:191|unique:users,email,{$this->user->id}",
                'user.email'
            )
        );

        try {
            DB::beginTransaction();

            $this->user->fill([
                'password' => empty($this->password) ? $this->user->password : bcrypt($this->password),
            ])->save();


            if ($this->currentRouteName == 'student.update') {
                $station = Station::find($this->selectedItem);
                $this->user->station()->associate($station);
                $this->user->save();
            } else {
                $direction = Direction::find($this->selectedItem);
                $direction->driver()->associate($this->user);
                $direction->save();
            }

            $this->user->syncRoles($this->role);

            session()->flash('message', __('The user was successfully updated'));
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollback();
        }
    }
}
