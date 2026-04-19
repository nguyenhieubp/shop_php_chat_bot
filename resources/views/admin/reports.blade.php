@extends('layouts.admin')

@section('title', 'Danh sách Báo cáo & Phản hồi')

@section('content')
    <div id="feedback-list" class="card admin-section">
        <h2>Phản hồi & Báo cáo từ khách hàng</h2>
        <p style="margin-bottom: 25px; color: #777;">Tất cả các tin nhắn báo lỗi hoặc góp ý từ khách hàng sẽ được hiển thị tại đây.</p>

        <table>
            <thead>
                <tr>
                    <th>Ngày gửi</th>
                    <th>Người gửi</th>
                    <th>Liên hệ</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                </tr>
                <tr class="search-row">
                    <th><input type="date" class="column-search"></th>
                    <th><input type="text" class="column-search" placeholder="Lọc tên..."></th>
                    <th><input type="text" class="column-search" placeholder="Lọc liên hệ..."></th>
                    <th><input type="text" class="column-search" placeholder="Lọc tiêu đề..."></th>
                    <th><input type="text" class="column-search" placeholder="Lọc nội dung..."></th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $item)
                <tr>
                    <td style="white-space: nowrap;">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $item->name ?? 'Ẩn danh' }}</strong></td>
                    <td>{{ $item->contact }}</td>
                    <td><span style="color: var(--primary); font-weight: 600;">{{ $item->subject ?? 'Không có tiêu đề' }}</span></td>
                    <td style="max-width: 400px; font-size: 13px; color: #555;">{{ $item->message }}</td>
                </tr>
                @endforeach
                @if($feedbacks->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center; color: #999; padding: 40px;">Chưa có báo cáo nào từ khách hàng.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
