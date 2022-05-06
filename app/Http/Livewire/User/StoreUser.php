<?php

namespace App\Http\Livewire\User;

use App\Models\Direction;
use App\Models\Station;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

class StoreUser extends Component
{
    public $currentRouteName;
    public $user;
    public $role;
    public $password = '';
    public $collection;
    public $selectedItem;

    public function mount(User $user)
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->user = $user;
        $this->role = Role::findByName(($this->currentRouteName == 'student.create') ? 'student' : 'driver', 'web')->id;
        $this->collection = ($this->currentRouteName == 'student.create') ? Station::all() : Direction::all();
        $this->selectedItem = $this->collection->first()->id;
        $this->user->gender = 'man';
        $this->user->status = true;
        $this->user->civil_status = 'single';
    }

    public function render()
    {
        return view('livewire.user.store-user');
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
            'password'         => 'required|string|min:8|max:100',
            'role'             => 'required|exists:roles,id',
            'selectedItem'     => 'required|exists:' . (($this->currentRouteName == 'student.create') ? 'stations' : 'directions') . ',id',
        ];
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function back()
    {
        return redirect()->route($this->currentRouteName == 'student.create' ? 'student.list' : 'driver.list');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->user->fill([
                'password' => bcrypt($this->password),
            ])->save();

            if ($this->currentRouteName == 'student.create') {
                $station = Station::find($this->selectedItem);
                $this->user->station()->associate($station);
                $this->user->save();
            } else {
                $direction = Direction::find($this->selectedItem);
                $direction->driver()->associate($this->user);
                $direction->save();
            }

            // dd($this->user, $direction);

            $this->user->assignRole($this->role);

            session()->flash('message', __('The user was successfully created'));
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}
