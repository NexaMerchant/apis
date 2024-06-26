<?php

namespace NexaMerchant\Apis\Mail\V1\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCodeNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new mailable instance.
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @return void
     */
    public function __construct(public $code, public $customer)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->customer->email)
            ->subject(trans('apis::app.emails.customers.sendcode.subject'))
            ->view('Apis::emails.v1.customers.sendcode', [
                'code' => $this->code,
            ]);
    }
}
