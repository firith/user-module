<?php

namespace Modules\User\Http\Livewire\Users;

use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class UserTable extends Component implements HasTable
{
    use InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    protected function getTableQuery(): Builder
    {
        return User::query()
            ->with('roles')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->sortable()->searchable(),
            BooleanColumn::make('is_enabled'),
            TextColumn::make('name')->sortable(),
            TextColumn::make('email')->sortable(),
            ViewColumn::make('roles')
                ->view('user::components.role-badges'),
            TextColumn::make('created_at')
                ->sortable()
                ->formatStateUsing(fn(Carbon $state) => $state->isoFormat('YYYY MMMM DD. HH:mm'))
        ];
    }

    protected function getTableActions(): array
    {
        return [
            LinkAction::make('edit')
                ->url(fn(User $record): string => route('usermodule.admin.users.edit', $record)),
        ];
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        return $query
            ->when($this->getTableSearchQuery(),
                fn(Builder $builder, $term) => $builder->where(fn(Builder $builder) => $builder
                    ->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%")
                ));
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('is_enabled')
                ->options([
                    false => 'Disabled',
                    true => 'Enabled',
                ])
                ->query(fn(Builder $query, $state): Builder => $query->when(! is_null($state['value']),
                    fn(Builder $query) => $query->where('is_enabled', $state['value']))),
        ];
    }

    public function render(): View
    {
        return view('user::livewire.users.table')
            ->layout('layouts.admin')
            ->layoutData(['pageTitle' => 'Users']);
    }

}
