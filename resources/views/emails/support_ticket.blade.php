<h2>New Support Ticket Submitted</h2>

<p><strong>Name:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Phone:</strong> {{ $data['phone'] }}</p>
<p><strong>Company:</strong> {{ $data['company'] ?? 'N/A' }}</p>
<p><strong>Category:</strong> {{ $data['category'] }}</p>
<p><strong>Priority:</strong> {{ $data['priority'] }}</p>
<p><strong>Subject:</strong> {{ $data['subject'] }}</p>
<p><strong>Description:</strong></p>
<p>{!! nl2br(e($data['description'])) !!}</p>
