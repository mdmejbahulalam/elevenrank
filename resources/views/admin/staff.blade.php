@extends('layouts.admin')
@section('title', 'Staff and roles')
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow">System</p><h1 style="margin:0">Staff and roles</h1></div>
	<a class="a-btn primary" href="mailto:?subject=ElevenRank%20HQ%20invite" title="Create the account with the staff:create command, then set its role">Invite staff</a></div>
<x-table :headings="['Name', 'Role', 'Last sign-in', 'Two-step', 'Status']">
@foreach ($staff as $s)
	<tr><td>{{ $s->name }}</td><td>{{ $roles[$s->staffRole()] ?? 'No access' }}</td>
		<td>{{ $s->last_login_at ? ($s->last_login_at->isToday() ? 'Today' : ($s->last_login_at->isYesterday() ? 'Yesterday' : $s->last_login_at->diffForHumans())) : 'Never' }}</td>
		<td>@if ($s->two_factor_confirmed_at)<span class="a-pill ok">On</span>@else<span class="a-pill warn">Off</span>@endif</td>
		<td><span class="a-pill ok">Active</span></td></tr>
@endforeach
</x-table>
<h2 style="margin:40px 0 16px">What each role can do</h2>
<x-table :headings="['Area', 'Super admin', 'Support', 'Finance']" :right="[]">
@foreach ($matrix as $area => $cells)
	<tr><td>{{ $area }}</td>@foreach (array_keys($roles) as $r)<td style="text-align:center">{{ $cells[$r] }}</td>@endforeach</tr>
@endforeach
</x-table>
<p class="a-muted" style="margin-top:8px">Read from the live permission rules.</p>
@endsection
