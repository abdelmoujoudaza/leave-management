<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class StoreUser extends Component
{
    public $user;
    public $role;
    public $roles;
    public $password = '';

    protected $rules = [
        'user.national_id'  => 'required|string|max:15',
        'user.firstname'    => 'required|string|max:100',
        'user.lastname'     => 'required|string|max:100',
        'user.position'     => 'nullable|string|max:100',
        'user.address'      => 'nullable|string|max:100',
        'user.birth'        => 'required|date',
        'user.gender'       => 'required|string|in:man,woman',
        'user.civil_status' => 'required|string|in:single,married',
        'user.email'        => 'required|string|email|max:191|unique:users,email',
        'password'          => 'required|string|min:8|max:100',
        'role'              => 'required|exists:roles,id',
    ];

    public function mount(User $user)
    {
        $this->user  = $user;
        $this->roles = Role::when(auth()->user()->hasRole('manager'), function ($query) {
            return $query->whereNotIn('name', ['admin']);
        })->get();
        $this->role  = $this->roles->first()->id;
        $this->user->gender = 'man';
        $this->user->status = 'active';
        $this->user->civil_status = 'single';
    }

    public function render()
    {
        return view('livewire.user.store-user');
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function back()
    {
        return redirect()->route('user.list');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->user->fill([
                'password' => bcrypt($this->password),
            ])->save();

            $this->user->assignRole($this->role);

            // session()->flash('message', 'Post successfully updated.');
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}
