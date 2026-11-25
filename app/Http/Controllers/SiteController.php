<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Setting;
use Illuminate\View\View;

class SiteController extends Controller
{
    private const UPDATED = '25 November 2026';

    public function pricing(): View
    {
        return view('site.pricing', ['plans' => Plan::where('active', true)->orderBy('sort')->get()]);
    }

    /** Who the seller is, from HQ Settings, with placeholders until they are filled in. */
    private function seller(): string
    {
        return (Setting::get('seller_name') ?: '[Legal name and address]').'. Contact: '.(Setting::get('support_email') ?: '[support address]').'.';
    }

    public function terms(): View
    {
        return $this->legal('Terms of service', [
            'Who we are' => 'ElevenRank is sold by '.$this->seller(),
            'Payments and tax' => 'Orders are processed by our payment provider, Paddle, who acts as the seller of record, collects any sales tax or VAT and shows the total before you pay. Prices are per licence term. Paddle\'s own terms apply to the payment.',
            'The free plugin' => 'ElevenRank for WordPress is free software released under the GNU General Public License, version 2 or later. The licence text that comes with the plugin governs its use, copying and modification.',
            'ElevenRank Pro' => 'ElevenRank Pro is also released under the GPL. A paid licence key entitles you to updates, downloads from your account and support for the licence term, for the number of sites in your plan. Pro keeps working after the term ends; updates and support stop until you renew.',
            'Accounts' => 'You are responsible for keeping your password safe and for what happens under your account. Keys are for your own sites or your clients\' sites; please do not publish them.',
            'Fair use' => 'Do not abuse the licence or download endpoints, attempt to access other customers\' data, or resell keys.',
            'Liability' => 'The software is provided without warranty, as the GPL describes. To the extent the law allows, our liability for any claim is limited to the amount you paid in the last twelve months.',
            'Changes' => 'We may update these terms. The date at the top shows when they last changed.',
        ]);
    }

    public function privacy(): View
    {
        return $this->legal('Privacy policy', [
            'Who we are' => 'The controller of your data is '.$this->seller(),
            'Payment provider' => 'Payments are taken by Paddle, which receives your payment details, billing country and e-mail address to process the order, and has its own privacy policy. We receive the order result, not your card number.',
            'Data transfers' => 'Our server and our payment provider may be in different countries. Where data leaves your region we rely on the safeguards the law requires.',
            'The free plugin' => 'The free ElevenRank plugin does not send data to us or anyone else. It contains no tracking.',
            'ElevenRank Pro' => 'When you activate a Pro licence, the plugin sends your licence key and your site address to this website to check the licence and look for updates. Nothing else is sent.',
            'Your account' => 'To sell and support Pro we store your name, e-mail address, password (hashed), your orders, your licence keys and the site addresses activated on them, and your support tickets.',
            'Why we keep it' => 'To provide your licence, send your receipts and keys, answer support requests, and meet accounting obligations.',
            'Who sees it' => 'Our staff. Payment details are handled by the payment provider and are not stored here. We do not sell your data.',
            'Your rights' => 'You can ask us to export or delete your data by opening a support ticket or writing to us. We keep records we must keep by law.',
            'Cookies' => 'This site uses only the cookies it needs to keep you signed in and to protect forms. There are no analytics or advertising cookies.',
        ]);
    }

    public function refunds(): View
    {
        return $this->legal('Refund policy', [
            '14 days' => 'If ElevenRank Pro is not right for you, ask for a refund within 14 days of your purchase and we will refund the order in full.',
            'How to ask' => 'Open a support ticket from your account, or write to '.(Setting::get('support_email') ?: '[support address]').', quoting your order number.',
            'How you are paid back' => 'The refund goes to the payment method you used, through our payment provider, and can take a few days to show.',
            'What happens' => 'The refunded licence key is switched off, so the sites using it will no longer be able to receive updates. Your WordPress sites and the free plugin are not affected.',
            'Renewals' => 'The same 14 days apply from the date of each renewal payment.',
        ]);
    }

    public function gpl(): View
    {
        return $this->legal('GPL statement', [
            'Free software' => 'ElevenRank and ElevenRank Pro are released under the GNU General Public License, version 2 or later, the same licence as WordPress. You may use, study, change and share the code under that licence.',
            'What the licence key is for' => 'The key does not restrict your GPL rights. It unlocks automatic updates, downloads from your account and support for the licence term.',
            'No tracking' => 'The free plugin makes no connections to outside servers. The Pro add-on contacts this website only for licence checks and updates.',
        ]);
    }

    private function legal(string $title, array $sections): View
    {
        return view('site.legal', ['title' => $title, 'sections' => $sections, 'updated' => self::UPDATED]);
    }
}
