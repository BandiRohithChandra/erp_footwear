@extends('layouts.app')

@section('content')
<style>
body {
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f3f6fb;
    color: #1f2937;
}

.container {
    max-width: 1100px;
    margin: -18px auto;
    padding: 20px;
}

/* Header */
h1 {
    text-align: center;
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 10px;
    color: #1f2937;
}

p.subtitle {
    text-align: center;
    color: #4b5563;
    font-size: 16px;
    margin-bottom: 50px;
}

/* Support Sections Grid */
.support-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    margin-bottom: 50px;
}

.support-card {
    background-color: #fff;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.support-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.support-card svg {
    width: 50px;
    height: 50px;
    margin-bottom: 15px;
}

.support-card h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #1f2937;
}

.support-card p {
    color: #6b7280;
    font-size: 14px;
}

/* Tickets Form */
form {
    background-color: #ffffff;
    padding: 6px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    margin-top: -4px;
}

form .form-group {
    margin-bottom: 22px;
}

form label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #1f2937;
}

form input, form select, form textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #d1d5db;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
}

form input:focus, form select:focus, form textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37,99,235,0.3);
    outline: none;
}

form textarea {
    resize: vertical;
}

button.submit-btn {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #ffffff;
    font-weight: 700;
    padding: 14px 36px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    display: block;
    margin: 30px auto 0;
    font-size: 17px;
    box-shadow: 0 8px 20px rgba(37,99,235,0.3);
    transition: all 0.3s ease;
}

button.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(37,99,235,0.5);
}

/* Two-column Grid for Form */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

@media(max-width: 768px){
    .grid-2 {
        grid-template-columns: 1fr;
    }
}

/* Highlight Required Fields */
label span {
    color: #ef4444;
}
</style>

<div class="container">

    <h1>General Support</h1>
    <p class="subtitle">Select a service below or submit a ticket and our team will assist you promptly.</p>

    <!-- Support Sections -->
    <div class="support-grid">
        <div class="support-card">
            <svg fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
            <h3>Tickets</h3>
            <p>Submit and track your support tickets easily in real-time.</p>
        </div>

        <div class="support-card">
            <svg fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
            <h3>FAQs & Knowledge Base</h3>
            <p>Browse frequently asked questions and detailed guides.</p>
        </div>

        <div class="support-card">
            <svg fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20l9-5-9-5-9 5 9 5z"/></svg>
            <h3>Guides & Tutorials</h3>
            <p>Step-by-step tutorials for easy problem solving and usage.</p>
        </div>

        <div class="support-card">
            <svg fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405M19 13V6l-1.405-1.405M19 13l-1.405 1.405M12 12l-1-1"/></svg>
            <h3>Problem Solving Assistant</h3>
            <p>AI-powered assistant to help you troubleshoot and resolve issues quickly.</p>
        </div>

        <div class="support-card">
            <svg fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3l2 2"/></svg>
            <h3>System Status</h3>
            <p>Check the health and status of our services in real-time.</p>
        </div>
    </div>

    <!-- Ticket Submission Form -->
    <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid-2">
            <div class="form-group">
                <label for="name">Full Name <span>*</span></label>
                <input type="text" id="name" name="name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="john@example.com" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="phone">Phone Number <span>*</span></label>
                <input type="text" id="phone" name="phone" placeholder="+91 98765 43210" required>
            </div>

            <div class="form-group">
                <label for="company">Company Name</label>
                <input type="text" id="company" name="company" placeholder="Your Company Name">
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="category">Issue Category <span>*</span></label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Technical Issue">Technical Issue</option>
                    <option value="Billing">Billing</option>
                    <option value="Feature Request">Feature Request</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="priority">Priority <span>*</span></label>
                <select id="priority" name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="subject">Subject <span>*</span></label>
            <input type="text" id="subject" name="subject" placeholder="Issue Subject" required>
        </div>

        <div class="form-group">
            <label for="description">Description <span>*</span></label>
            <textarea id="description" name="description" rows="5" placeholder="Describe your issue in detail" required></textarea>
        </div>

        <div class="form-group">
            <label for="attachment">Attachment (Optional)</label>
            <input type="file" id="attachment" name="attachment">
        </div>

        <button type="submit" class="submit-btn">Submit Ticket</button>
    </form>

</div>
@endsection
