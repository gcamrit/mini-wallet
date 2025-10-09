<?php

use App\Broadcasting\TransactionChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}.transactions', TransactionChannel::class);
