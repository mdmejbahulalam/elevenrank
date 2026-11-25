<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="color-scheme" content="light"><title>@yield('subject')</title></head>
<body style="margin:0;background:#f5f6f8;font-family:system-ui,-apple-system,'Segoe UI',Roboto,Arial,sans-serif;color:#101828">
<div style="display:none;max-height:0;overflow:hidden">@yield('preview')</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f6f8"><tr><td align="center" style="padding:24px 12px">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border:1px solid #e4e7ec;border-radius:12px">
<tr><td style="padding:20px 28px;border-bottom:1px solid #e4e7ec"><a href="{{ url('/') }}" style="text-decoration:none;color:#101828;font-size:18px;font-weight:800;letter-spacing:-.02em"><img src="{{ url('/assets/mark.png') }}" width="28" height="28" alt="" style="vertical-align:middle;border:0;border-radius:7px"> <span style="vertical-align:middle;margin-left:6px">Eleven<span style="color:#4338ca">Rank</span></span></a></td></tr>
<tr><td style="padding:28px;font-size:16px;line-height:24px">
<h1 style="margin:0 0 16px;font-size:24px;line-height:30px;color:#101828">@yield('heading')</h1>
@yield('body')
</td></tr>
<tr><td style="padding:16px 28px 20px;background:#f5f6f8;border-top:1px solid #e4e7ec;font-size:13px;line-height:20px;color:#475467;border-radius:0 0 12px 12px">
<p style="margin:0 0 8px">You get this because of your ElevenRank account. It is a service email, so there is no unsubscribe link.</p>
<p style="margin:0">{{ \App\Models\Setting::get('seller_name') ?: '[Legal name and address of the seller]' }} · {{ \App\Models\Setting::get('support_email') ?: '[support address]' }}</p>
</td></tr>
</table></td></tr></table>
</body></html>
