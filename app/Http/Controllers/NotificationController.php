<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Exibe a lista de notificações do usuário.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Buscar notificações do usuário com paginação
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Exibe uma notificação específica.
     */
    public function show(int $notificationId): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->firstOrFail();
            
        // Marcar como lida se não estiver lida
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        
        return view('notifications.show', compact('notification'));
    }

    /**
     * Marca uma notificação como lida.
     */
    public function markAsRead(int $notificationId): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
        }
        
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->firstOrFail();
            
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true, 'message' => 'Notificação marcada como lida!']);
    }

    /**
     * Marca todas as notificações como lidas.
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
        }
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json(['success' => true, 'message' => 'Todas as notificações foram marcadas como lidas!']);
    }

    /**
     * Remove uma notificação.
     */
    public function destroy(int $notificationId): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
        }
        
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->firstOrFail();
            
        $notification->delete();
        
        return response()->json(['success' => true, 'message' => 'Notificação removida com sucesso!']);
    }

    /**
     * Retorna estatísticas das notificações.
     */
    public function stats(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
        }
        
        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
            'read' => Notification::where('user_id', $user->id)->whereNotNull('read_at')->count(),
        ];
        
        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Retorna as notificações mais recentes.
     */
    public function latest(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
        }
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json(['success' => true, 'data' => $notifications]);
    }

    /**
     * Envia mensagem do gestor.
     */
    public function sendManagerMessage(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,warning,error,success'
        ]);
        
        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => 'Mensagem do Gestor',
            'message' => $request->message,
            'type' => $request->type,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
    }

    /**
     * Envia notificação em massa.
     */
    public function sendBulkNotification(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,warning,error,success'
        ]);
        
        $notifications = [];
        foreach ($request->user_ids as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        Notification::insert($notifications);
        
        return response()->json(['success' => true, 'message' => 'Notificações enviadas com sucesso!']);
    }
} 