/**
 * Gerar certificado para simulado
 * 
 * @param User $user Usuário que receberá o certificado
 * @param $simulado Simulado aprovado
 * @param $tentativa Tentativa aprovada
 * @return Certificate|null Certificado gerado ou null em caso de falha
 */
public function generateSimuladoCertificate(User $user, $simulado, $tentativa): ?Certificate
{
    // Verificar se a tentativa pertence ao usuário e ao simulado
    if ($tentativa->user_id !== $user->id || $tentativa->simulado_id !== $simulado->id) {
        return null;
    }
    
    // Verificar se a tentativa foi aprovada
    if ($tentativa->score < $simulado->passing_score) {
        return null;
    }
    
    // Gerar número de certificado único
    $certificateNumber = 'HCP-SIM-' . date('Y') . '-' . str_pad($simulado->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
    
    $certificateData = [
        'user_id' => $user->id,
        'type' => 'simulado',
        'title' => "Certificado - {$simulado->titulo}",
        'description' => "Certificado de aprovação no simulado {$simulado->titulo} do programa de onboarding da Hemera Capital Partners.",
        'category' => $simulado->categoria,
        'reference_id' => $simulado->id,
        'certificate_number' => $certificateNumber,
        'issued_at' => now(),
        'verification_code' => $this->generateVerificationCode(),
        'metadata' => [
            'simulado_titulo' => $simulado->titulo,
            'simulado_categoria' => $simulado->categoria,
            'simulado_nivel' => $simulado->nivel,
            'score' => $tentativa->score,
            'completion_date' => $tentativa->finalizado_em->toDateString(),
            'pontos_recompensa' => $simulado->pontos_recompensa,
        ]
    ];
    
    $certificate = Certificate::create($certificateData);
    
    // Gerar PDF do certificado
    $this->generateCertificatePDF($certificate);
    
    // Enviar notificação
    $this->notificationService->sendCertificateNotification($user, $certificate);
    
    return $certificate;
}