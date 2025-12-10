<?php

arch('models has tenancy traits')
    ->expect('App\Models')
    ->toUse(\Illuminate\Database\Eloquent\Factories\HasFactory::class);

arch('no debugging calls are used')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed();

arch()->preset()->security();
arch()->preset()->php();
