@extends('components.layouts.simple')

@section('title', 'Privacy Policy')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69] text-white">
    <!-- Header -->
    <section class="relative py-16 lg:py-24 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Privacy Policy</h1>
                <p class="text-[#D1C4E9]">Effective date: {{ now()->toFormattedDateString() }}</p>
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto space-y-8">
                <!-- Intro -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">1. Who We Are</h2>
                    <p class="text-[#D1C4E9]">Royal Maids Hub ("Royal Maids", "we", "us", "our") provides domestic staffing and related services. We are committed to protecting your privacy and handling personal data lawfully, fairly, and transparently.</p>
                    <p class="text-[#D1C4E9] mt-3">This Policy explains how we collect, use, disclose, store, and protect your information when you use our website, submit booking requests, contact us, or otherwise interact with us.</p>
                </div>

                <!-- Scope & Laws -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">2. Scope and Applicable Laws</h2>
                    <p class="text-[#D1C4E9]">This Policy applies to processing carried out by Royal Maids Hub as a controller. We comply with applicable laws, including where relevant: the Uganda Data Protection and Privacy Act, 2019 ("UDPPA"), the EU/UK General Data Protection Regulation ("GDPR"/"UK GDPR"), the California Consumer Privacy Act ("CCPA"/"CPRA"), and other local data protection laws, to the extent they apply to our operations and to you.</p>
                </div>

                <!-- Data We Collect -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">3. Information We Collect</h2>
                    <ul class="list-disc list-inside space-y-2 text-[#D1C4E9]">
                        <li><span class="font-semibold text-white">Identity and contact</span>: name, phone, email, address, city/division/parish, national ID/passport (where you upload it).</li>
                        <li><span class="font-semibold text-white">Household and service preferences</span>: home type, rooms, family size, pets, preferred service mode, work days, start/end dates, additional requirements.</li>
                        <li><span class="font-semibold text-white">Maid preferences</span>: interest in specific maids (e.g., from our public listings).</li>
                        <li><span class="font-semibold text-white">Technical</span>: IP address, device/browser info, pages visited, cookies/analytics data (see Cookies below).</li>
                        <li><span class="font-semibold text-white">Communications</span>: messages you send via our contact forms, support requests, and call/email records.</li>
                        <li><span class="font-semibold text-white">Account/transaction</span> (if applicable): basic account profile, bookings, payments, invoices and receipts.</li>
                    </ul>
                    <p class="text-[#D1C4E9] mt-3">We collect data directly from you, from your device/browser, and, where appropriate and lawful, from third parties (e.g., payment providers), always in accordance with applicable law.</p>
                </div>

                <!-- Purposes & Legal Bases -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">4. How We Use Your Information</h2>
                    <ul class="list-disc list-inside space-y-2 text-[#D1C4E9]">
                        <li>Provide and manage our services (process booking requests, match with maids, manage schedules).</li>
                        <li>Communicate with you (confirmations, updates, support, service notices).</li>
                        <li>Personalize and improve our website, services, and user experience.</li>
                        <li>Maintain security, prevent fraud/abuse, and enforce our terms.</li>
                        <li>Meet legal, regulatory, and tax obligations.</li>
                        <li>With your consent, send marketing communications (you can opt out at any time).</li>
                    </ul>
                    <p class="text-[#D1C4E9] mt-3"><span class="font-semibold text-white">Legal bases (GDPR/UK GDPR)</span>: performance of a contract or steps prior to entering a contract; legitimate interests (e.g., service improvement, security); legal obligation; consent (for certain marketing/cookies or special categories where applicable). Under UDPPA, we process data lawfully and with your knowledge/consent where required.</p>
                </div>

                <!-- Sharing -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">5. When We Share Your Information</h2>
                    <p class="text-[#D1C4E9]">We do not sell your personal information. We may share limited data with:</p>
                    <ul class="list-disc list-inside space-y-2 text-[#D1C4E9] mt-2">
                        <li><span class="font-semibold text-white">Service providers/processors</span> (hosting, analytics, communications, storage) under contracts that require confidentiality and security.</li>
                        <li><span class="font-semibold text-white">Maids/placement partners</span> only as necessary to fulfill a booking or pre-booking request you initiate.</li>
                        <li><span class="font-semibold text-white">Legal/regulatory authorities</span> where required by law or to protect rights, safety, or security.</li>
                        <li><span class="font-semibold text-white">Business transfers</span> in the event of a merger, acquisition, or asset sale, subject to this Policy.</li>
                    </ul>
                </div>

                <!-- International Transfers -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">6. International Data Transfers</h2>
                    <p class="text-[#D1C4E9]">Where data is transferred across borders, we implement appropriate safeguards (e.g., Standard Contractual Clauses, adequacy decisions) to protect your information in accordance with applicable law.</p>
                </div>

                <!-- Retention -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">7. Data Retention</h2>
                    <p class="text-[#D1C4E9]">We keep personal data only as long as necessary for the purposes described above or as required by law (e.g., financial and tax requirements). When no longer needed, we securely delete or anonymize data.</p>
                </div>

                <!-- Security -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">8. Security</h2>
                    <p class="text-[#D1C4E9]">We use technical and organizational measures designed to protect personal data, including encryption in transit, access controls, secure storage, and staff training. No method is 100% secure; we continually review and improve our practices.</p>
                </div>

                <!-- Your Rights -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">9. Your Rights</h2>
                    <p class="text-[#D1C4E9]">Subject to law, you may have the right to access, correct, update, delete, or restrict your personal data; object to processing; withdraw consent; and data portability. Requests can be made using the contact details below. We will respond within the timelines required by applicable law.</p>
                    <ul class="list-disc list-inside space-y-2 text-[#D1C4E9] mt-2">
                        <li><span class="font-semibold text-white">UDPPA (Uganda)</span>: rights to access, rectification, erasure, and to be informed of processing.</li>
                        <li><span class="font-semibold text-white">GDPR/UK GDPR</span>: Arts. 12–23 (access, rectification, erasure, restriction, portability, objection).</li>
                        <li><span class="font-semibold text-white">CCPA/CPRA</span>: rights to know, delete, correct, and opt-out of sale/share of personal information; non-discrimination.</li>
                    </ul>
                </div>

                <!-- Cookies -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">10. Cookies & Analytics</h2>
                    <p class="text-[#D1C4E9]">We use cookies and similar technologies to operate and improve the site, understand usage, and tailor content. You can control cookies via your browser settings and, where required, via our consent tools. Some features may not function without certain cookies.</p>
                </div>

                <!-- Children -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">11. Children</h2>
                    <p class="text-[#D1C4E9]">Our services are not directed to children under the age permitted by law in your jurisdiction. We do not knowingly collect personal data from children without appropriate consent. If you believe a child has provided data to us, please contact us.</p>
                </div>

                <!-- Changes -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">12. Changes to this Policy</h2>
                    <p class="text-[#D1C4E9]">We may update this Policy to reflect operational, legal, or regulatory changes. We will post updates on this page with a new effective date. Material changes may also be communicated via email or prominent notice.</p>
                </div>

                <!-- Contact -->
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold mb-3">13. Contact Us</h2>
                    <p class="text-[#D1C4E9]">To exercise your rights or ask questions about this Policy, contact us:</p>
                    <ul class="list-disc list-inside space-y-1 text-[#D1C4E9] mt-2">
                        <li>Email: <a href="mailto:info@royalmaidshub.com" class="text-[#F5D06A] underline">info@royalmaidshub.com</a></li>
                        <li>Phone: +256 703 173206</li>
                        <li>Address: Mpelerwe Mugalu Zone</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')
</div>
@endsection


