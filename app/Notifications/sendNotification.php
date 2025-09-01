<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use Illuminate\Bus\Queueable;
use App\Models\Admin\Currency;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use Illuminate\Notifications\Notification;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Notifications\Messages\MailMessage;

class sendNotification extends Notification
{
    use Queueable;
    public $user;
    public $data;
    public $trx_id;

    
    public function __construct($user, $data, $trx_id)
    {
        $this->user = $user;
        $this->data = $data;
        $this->trx_id = $trx_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user = $this->user;
        $data = $this->data;
        $identifier_data = TemporaryData::where('identifier',$data['request_data']['identifier'])->first();
        
        $trx_id = $this->trx_id;
        $date = Carbon::now();
        $datetime = dateFormat('Y-m-d h:i:s A', $date);
        $basic_settings = BasicSettingsProvider::get();
        $contact_section_slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact              = SiteSections::getData($contact_section_slug)->first();

        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        return (new MailMessage)
            ->subject("Your Recent Remittance - MTCN: ". $trx_id)
            ->view('frontend.email.confirmation', [
                'identifier_data'   => $identifier_data,
                'data'              => $data,
                'user'              => $user,
                'trx_id'            => $trx_id,
                'receiver_currency' => $receiver_currency,
                'contact'           => $contact,
                'basic_settings'    => $basic_settings
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
