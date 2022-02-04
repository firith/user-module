<?php

namespace Modules\User\Http\Livewire\Users;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public User $user;

    public bool $showModal = false;

    public function mount()
    {
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'is_enabled' => $this->user->is_enabled,
        ]);
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required(),

                                    Forms\Components\TextInput::make('email')
                                        ->required()
                                        ->unique('users', 'email', $this->user)
                                        ->model($this->user)
                                        ->email(),

                                    Forms\Components\Toggle::make('is_enabled')
                                        ->hidden(fn() => auth()->id() === $this->user->id),
                                ]),

                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->minLength(8)
                                        ->same('passwordConfirmation')
                                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                        ->dehydrated(fn(callable $get) => strlen($get('password')) > 0)
                                        ->reactive(),

                                    Forms\Components\TextInput::make('passwordConfirmation')
                                        ->password()
                                        ->dehydrated(false),
                                ]),

                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('Roles'),
                                    Forms\Components\BelongsToManyCheckboxList::make('roles')
                                        ->label('')
                                        ->relationship('roles', 'name')
                                        ->model($this->user),
                                ])
                                ->hidden(fn() => Role::query()->count() === 0),

                        ])
                        ->columnSpan(['lg' => 2]),

                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('Created At')
                                        ->content($this->user->created_at->isoFormat('YYYY MMMM DD. HH:mm')),
                                    Forms\Components\Placeholder::make('Updated At')
                                        ->content($this->user->updated_at->isoFormat('YYYY MMMM DD. HH:mm')),
                                ]),

                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\View::make('user::components.delete-user'),
                                ])
                                ->hidden(fn() => auth()->id() === $this->user->id),
                        ])
                        ->columnSpan(1),
                ]),
        ];
    }

    public function submit(): void
    {
        $this->user->forceFill($this->form->getState())
            ->save();

        $this->dispatchBrowserEvent('toast',
            ['type' => 'success', 'message' => 'User was updated.']);

        unset($this->data['password']);
        unset($this->data['passwordConfirmation']);
    }

    public function deleteUser(): Redirector
    {
        $this->user->delete();

        return redirect()->route('usermodule.users.index')
            ->with('toast', ['type' => 'success', 'message' => 'User was deleted.']);
    }

    public function render(): View
    {
        return view('user::livewire.users.form')
            ->layout('layouts.admin')
            ->layoutData(['pageTitle' => 'Edit User']);
    }
}
