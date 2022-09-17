<style>
    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .badge-success {
        color: #fff;
        background-color: #28a745;
    }
    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }
    .badge-info {
        color: #fff;
        background-color: #17a2b8;
    }
</style>

@if(isset($status) && $status == 0)
    <span class="badge badge-danger ml-2">declined </span>
@elseif(isset($status) && $status == 1)
    <span class="badge badge-info ml-2">Invited</span>
@elseif(isset($status) && $status == 2)
    <span class="badge badge-success ml-2">Accepted</span>
@endif
