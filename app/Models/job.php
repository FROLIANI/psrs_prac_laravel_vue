<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illumiante\Database\Eloquent\Factories\HasFactory;

class job extends Model
{
    use HasFactory;
	
	protected $fillable =[
	'title',
	'department',
	'location',
	'salary',
	'is_active'
	]
}
