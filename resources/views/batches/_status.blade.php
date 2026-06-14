@if ($status === 'released')
    <span class="text-[10px] font-bold text-pass bg-emerald-50 border border-emerald-200 rounded-md px-2 py-1">RELEASED</span>
@elseif ($status === 'rejected')
    <span class="text-[10px] font-bold text-danger bg-red-50 border border-red-200 rounded-md px-2 py-1">REJECTED</span>
@else
    <span class="text-[10px] font-bold text-amber bg-amber-50 border border-amber-200 rounded-md px-2 py-1">TESTING</span>
@endif
