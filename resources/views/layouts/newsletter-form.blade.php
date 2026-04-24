<form action="{{ route('subscribers.subscribe') }}" method="POST" class="d-flex gap-2">
    @csrf
    <input type="hidden" name="type" value="email">
    <input type="hidden" name="source" value="footer_form">
    <input type="email" name="email" class="form-control" placeholder="Your email address" required>
    <button type="submit" class="btn btn-primary text-nowrap">Subscribe</button>
</form>
@if(session('subscribed'))
    <small class="text-success mt-1 d-block">✓ You're subscribed!</small>
@endif