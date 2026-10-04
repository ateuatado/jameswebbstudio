<?php

namespace App\Models;

use CodeIgniter\Model;

class ThreadsOpportunityModel extends Model
{
    protected $table = 'threads_opportunities';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'assigned_user_id', 'threads_username', 'threads_post_url', 'source_text', 'published_relative', 'hashtags', 'original_text',
        'context_category', 'priority', 'city', 'status', 'comment_copy', 'direct_copy',
        'page_title', 'page_intro', 'offer_copy', 'cta_label', 'cta_url', 'whatsapp_owner', 'whatsapp_number', 'page_token',
        'is_page_active', 'last_action_at', 'next_action_at', 'response_notes',
        'proposal_value_cents', 'closed_reason',
    ];

    protected $validationRules = [
        'threads_username' => 'required|max_length[120]',
        'threads_post_url' => 'permit_empty|valid_url_strict|max_length[512]',
        'original_text' => 'required',
        'context_category' => 'permit_empty|max_length[80]',
        'priority' => 'required|in_list[high,medium,low]',
        'status' => 'required|in_list[identified,drafting,ready,commented,direct_sent,replied,conversation,proposal_sent,scheduled,won,not_interested,do_not_contact,expired]',
        'page_token' => 'required|exact_length[64]',
    ];

    public const STATUSES = [
        'identified' => 'Identificada', 'drafting' => 'Em preparação', 'ready' => 'Pronta para revisão',
        'commented' => 'Comentário publicado', 'direct_sent' => 'Direct enviado', 'replied' => 'Respondeu',
        'conversation' => 'Conversa iniciada', 'proposal_sent' => 'Proposta enviada', 'scheduled' => 'Agendou',
        'won' => 'Convertida', 'not_interested' => 'Sem interesse', 'do_not_contact' => 'Não contatar', 'expired' => 'Expirada',
    ];

    public const CATEGORIES = ['autocuidado', 'novo visual', 'aniversário', 'profissão', 'recomeço', 'ensaio explícito', 'afinidade estética', 'outro'];

    public function withAssignedUser(): self
    {
        $this->select('threads_opportunities.*, users.username AS assigned_username, users.display_name AS assigned_display_name');
        $this->join('users', 'users.id = threads_opportunities.assigned_user_id', 'left');
        return $this;
    }

    public function findByToken(string $token): ?object
    {
        return $this->where('page_token', $token)->where('is_page_active', 1)->first();
    }

    public function pageViews(int $id): int
    {
        return (int) $this->db->table('threads_opportunity_events')
            ->where('opportunity_id', $id)->where('event_type', 'page_view')->countAllResults();
    }
}
