<?php

namespace Modules\User\Http\Livewire\Users;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Redirector;
use Spatie\Permission\Models\Role;

class CreateForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill([
            'name' => '',
            'email' => '',
            'is_enabled' => true,
            'roles' => [],
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
                                        ->unique('users', 'email')
                                        ->email(),

                                    Forms\Components\Toggle::make('is_enabled'),
                                ]),

                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->required()
                                        ->minLength(8)
                                        ->same('passwordConfirmation')
                                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                        ->dehydrated(fn(callable $get) => strlen($get('password')) > 0),

                                    Forms\Components\TextInput::make('passwordConfirmation')
                                        ->password()
                                        ->required()
                                        ->dehydrated(false),
                                ]),

                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('Roles'),
                                    Forms\Components\BelongsToManyCheckboxList::make('roles')
                                        ->label('')
                                        ->relationship('roles', 'name')
                                ])
                                ->hidden(fn() => Role::query()->count() === 0),
                        ])
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                ]),
        ];
    }

    public function submit()
    {
        $class = config('user.model');
        $user = new $class();
        $user->forceFill($this->form->getState())
            ->save();

        $user->roles()->sync($this->data['roles']);

        return redirect()->route('usermodule.admin.users.edit', $user)
            ->with('toast', ['type' => 'success',  'message' => 'User was created.']);
    }

    public function getFormModel(): string
    {
        return config('user.model');
    }

    public function render(): View
    {
        return view('user::livewire.users.form')
            ->layout('nore::layouts.admin')
            ->layoutData(['pageTitle' => 'Edit User']);
    }
}
