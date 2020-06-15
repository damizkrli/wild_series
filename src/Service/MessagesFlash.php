<?php


namespace App\Service;


class MessagesFlash
{
    public function create(string $data): string
    {
        return 'Your ' . $data . ' has been create';
    }

    public function delete(string $data): string
    {
        return 'Your ' . $data . ' has been delete';
    }

    public function update(string $data): string
    {
        return 'Your ' . $data . ' has been modify';
    }
}
