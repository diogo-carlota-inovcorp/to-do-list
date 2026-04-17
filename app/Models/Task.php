<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'category',
        'category_color',
        'user_id',
        'is_completed',
        'status'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date',
    ];

    // Cores para as prioridades
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'alta' => 'red',
            'media' => 'orange',
            'baixa' => 'green',
            default => 'gray',
        };
    }

    // Cores e estilos para status
    public function getStatusInfoAttribute()
    {
        return match($this->status) {
            'pendente' => ['color' => 'gray', 'icon' => '⭕', 'label' => 'Pendente', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
            'iniciado' => ['color' => 'blue', 'icon' => '🔵', 'label' => 'Iniciado', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
            'em_andamento' => ['color' => 'purple', 'icon' => '🟣', 'label' => 'Em Andamento', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
            'concluido' => ['color' => 'green', 'icon' => '✅', 'label' => 'Concluído', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
            'expirado' => ['color' => 'red', 'icon' => '❌', 'label' => 'Expirado', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
            default => ['color' => 'gray', 'icon' => '⭕', 'label' => 'Pendente', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
        };
    }

    // Calcular dias restantes
    public function getDaysLeftAttribute()
    {
        if (!$this->due_date) return null;

        $today = Carbon::today();
        $dueDate = Carbon::parse($this->due_date);
        $days = $today->diffInDays($dueDate, false);

        return $days;
    }

    // Status da data
    public function getDueDateStatusAttribute()
    {
        if (!$this->due_date) return null;

        $days = $this->days_left;

        if ($days < 0) return 'overdue';
        if ($days == 0) return 'today';
        if ($days <= 3) return 'soon';
        return 'ok';
    }

    // Atualizar status automaticamente baseado na data
    public function updateStatusBasedOnDate()
    {
        if ($this->status === 'concluido') {
            return;
        }

        if ($this->due_date && Carbon::parse($this->due_date)->isPast()) {
            $this->update(['status' => 'expirado']);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('accepted')
                    ->withTimestamps();
    }


    public function isOverdue(): bool
    {
        return $this->due_date && Carbon::parse($this->due_date)->isPast() && !$this->is_completed;
    }

}
