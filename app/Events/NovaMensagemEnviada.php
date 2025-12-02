<?php

namespace App\Events;

use App\Models\Mensagem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Broadcasting\SerializesModels; // Removido, não existe
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels as QueueSerializesModels;

class NovaMensagemEnviada implements ShouldBroadcast
{
    use InteractsWithSockets, QueueSerializesModels;

    public $mensagem;

    public function __construct(Mensagem $mensagem)
    {
        $this->mensagem = $mensagem;
    }

    public function broadcastOn()
    {
        // Canal privado para chat entre empresa e usuário
        return new PrivateChannel('chat.' . $this->mensagem->remetente_id . '.' . $this->mensagem->destinatario_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->mensagem->id,
            'remetente_id' => $this->mensagem->remetente_id,
            'remetente_tipo' => $this->mensagem->remetente_tipo,
            'destinatario_id' => $this->mensagem->destinatario_id,
            'destinatario_tipo' => $this->mensagem->destinatario_tipo,
            'mensagem' => $this->mensagem->mensagem,
            'horario' => $this->mensagem->horario,
        ];
    }
}
