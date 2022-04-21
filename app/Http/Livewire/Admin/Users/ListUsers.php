<?php

namespace App\Http\Livewire\Admin\Users;
use App\Http\Livewire\Admin\AdminComponent;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ListUsers extends AdminComponent
{

    public $state = [];
    public $userIdBeingRemoved = null;
    public $showEditModel = false;
    public $user;
    public function addNew()
    {
        $this->state= '';
        $this->showEditModel = false;
        $this->dispatchBrowserEvent('show-form');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createUser()
    {

        $validatedData = Validator::make($this->state,[
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|confirmed|min:6',
    ])->validate();

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

//        session()->flash('message','User added successfully!');

        $this->dispatchBrowserEvent('hide-form',['message'=>'User added successfully']);

        return redirect()->back();
    }

    public function edit(User $user){
        $this->showEditModel=true;
        $this->user = $user;
        $this->state = $user->toArray();
        $this->dispatchBrowserEvent('show-form');
    }

    public function updateUser(){

        $validatedData = Validator::make($this->state,[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$this->user->id,
            'password'=>'sometimes|confirmed|min:6',
        ])->validate();

        if(!empty($validatedData['password'])){
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
        $this->user->update($validatedData);
//        session()->flash('message','User added successfully!');
        $this->dispatchBrowserEvent('hide-form',['message'=>'User updated successfully']);
    }

    public function confirmUserRemoval($userId)
    {
        $this->userIdBeingRemoved = $userId;
        $this->dispatchBrowserEvent('show-delete-modal');

    }

    public function deleteUser()
    {
        $user = User::findorfail($this->userIdBeingRemoved);
        $user->delete();
        $this->dispatchBrowserEvent('hide-delete-modal',['message'=>'User deleted successfully']);
    }
    public function render()
    {
        $users = User::orderBy('id','asc')->paginate(5);
        return view('livewire.admin.users.list-users',[
            'users'=>$users,
        ]);
    }
}
