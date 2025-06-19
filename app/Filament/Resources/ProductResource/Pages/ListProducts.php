<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('chatbot')
                ->label('Add with Chat')
                ->url(route('chat.index'))
                ->color('info')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->openUrlInNewTab(), // opcional
        ];
    }
}
