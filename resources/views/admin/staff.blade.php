@extends('layouts.admin')
@section('title', 'Staff and roles')
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow">System</p><h1 style="margin:0">Staff and roles</h1></div>
	</div>
<form method="post" action="/admin/staff" class="a-card a-row" style="margin-bottom:24px;align-items:flex-end;flex-wrap:wrap;gap:12px">@csrf
	<div><label class="a-label" for="iname" style="margin-top:0">Name</label><input id="iname" name="name" required maxlength="120"></div>
	<div><label class="a-label" for="iemail" style="margin-top:0">Email</label><input id="iemail" name="email" type="email" required></div>
	<div><label class="a-label" for="irole" style="margin-top:0">Role</label><select id="irole" name="role">@foreach ($roles as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
	<button class="a-btn primary">Invite staff</button>
</form>
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
