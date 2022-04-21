<?php

namespace App\Http\Livewire\Admin\Appointments;

use App\Http\Livewire\Admin\AdminComponent;
use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;

class ListAppointments extends AdminComponent
{
    protected $listeners = ['deleteConfirmed'=>'deleteAppointment'];
    public $appointIdRemoved = null;
    public function confirmAppointment($id)
    {
       $this->appointIdRemoved = $id;
       $this->dispatchBrowserEvent('show-delete-confirmation');
    }

    public function render()
    {
        $appointments = Appointment::with('client')->latest()->paginate();
        return view('livewire.admin.appointments.list-appointments',[
            'appointments'=>$appointments
        ]);
    }

    public function deleteAppointment()
    {
        $appointment = Appointment::findorfail($this->appointIdRemoved);
        $appointment->delete();
        $this->dispatchBrowserEvent('deleted',['message'=>'Appointment deleted successfully!!']);

    }
}
