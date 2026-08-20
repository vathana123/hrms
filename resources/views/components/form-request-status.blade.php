@props(['formRequest', 'latestApproval' => null, 'completedClass' => 'qbg-teal'])

@php
    $latestApproval ??= $formRequest?->latestApproval;
    $latestApprovalLine = $latestApproval?->approvalLine;
    $status = $formRequest?->status;
    $approvalStatus = $latestApproval?->status;
@endphp

<span
    {{ $attributes->merge([
        'class' =>
            'qstatus-badge qcaption qrounded-xs ' .
            ($status == 'Pending' || $approvalStatus == 'Pending' ? 'qbg-blue ' : '') .
            ($status == 'Returned' || $approvalStatus == 'Returned' ? 'qbg-yellow ' : '') .
            ($status == 'Rejected' || $approvalStatus == 'Rejected' ? 'qbg-red ' : '') .
            ($status == 'Approved' ? $completedClass . ' ' : '') .
            ($status != 'Approved' && $approvalStatus == 'Approved' ? 'qbg-green ' : '') .
            ($status == 'Cancelled' || $approvalStatus == 'Cancelled' ? 'qbg-red ' : ''),
    ]) }}>
    @if ($status == 'Approved')
        Completed
    @elseif ($status == 'Cancelled')
        Cancelled
    @elseif (!$latestApproval)
        No line to approve.
    @elseif ($approvalStatus == 'Pending')
        @if (auth()->user()->id == $latestApproval->approver_id)
            Waiting for your approval
        @else
            Pending on line {{ $latestApprovalLine?->name ?? 'N/A' }}
        @endif
    @elseif ($approvalStatus == 'Returned')
        Returned by {{ $latestApprovalLine?->name ?? 'N/A' }}
    @elseif ($approvalStatus == 'Rejected')
        Rejected by {{ $latestApprovalLine?->name ?? 'N/A' }}
    @else
        {{ $approvalStatus == 'Approved' ? '' : $approvalStatus ?? $status }}
    @endif
</span>
