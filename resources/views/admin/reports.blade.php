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
                    <th style="text-align: center;">Hành động</th>
                </tr>
                <tr class="search-row">
                    <th><input type="date" class="column-search"></th>
                    <th><input type="text" class="column-search" placeholder="Lọc tên..."></th>
                    <th><input type="text" class="column-search" placeholder="Lọc liên hệ..."></th>
                    <th><input type="text" class="column-search" placeholder="Lọc tiêu đề..."></th>
                    <th><input type="text" class="column-search" placeholder="Lọc nội dung..."></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $item)
                <tr>
                    <td style="white-space: nowrap;">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $item->name ?? 'Ẩn danh' }}</strong></td>
                    <td>{{ $item->contact }}</td>
                    <td><span style="color: var(--primary); font-weight: 600;">{{ $item->subject ?? 'Không có tiêu đề' }}</span></td>
                    <td style="max-width: 320px;">
                        <div style="font-size: 13px; color: #555; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px;">{{ $item->message }}</span>
                            <button type="button" 
                                    class="view-report-btn"
                                    data-name="{{ $item->name ?? 'Ẩn danh' }}" 
                                    data-contact="{{ $item->contact }}" 
                                    data-date="{{ $item->created_at->format('d/m/Y H:i') }}" 
                                    data-subject="{{ $item->subject ?? 'Không có tiêu đề' }}"
                                    style="background-color: var(--secondary); color: var(--primary); border: 1px solid var(--border); padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 600; white-space: nowrap; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-eye"></i> Xem
                            </button>
                            <div class="hidden-message" style="display: none;">{{ $item->message }}</div>
                        </div>
                    </td>
                    <td style="white-space: nowrap; text-align: center;">
                        <form action="{{ route('admin.reports.delete', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa báo cáo/phản hồi này?');" style="display: inline-block;">
                            @csrf
                            <button type="submit" style="background-color: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s ease;">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($feedbacks->isEmpty())
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 40px;">Chưa có báo cáo nào từ khách hàng.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal Chi Tiết Phản Hồi -->
    <div id="reportModal" class="report-modal" style="display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div class="report-modal-content" style="background-color: #ffffff; margin: auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 600px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; position: relative; animation: slideIn 0.3s cubic-bezier(0.19, 1, 0.22, 1); box-sizing: border-box;">
            <span class="report-modal-close" style="position: absolute; right: 24px; top: 20px; font-size: 28px; font-weight: bold; color: #a0aec0; cursor: pointer; transition: color 0.2s ease; line-height: 1;">&times;</span>
            <h3 style="font-size: 22px; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; font-weight: 800; color: #1a202c; letter-spacing: -0.02em;">Chi tiết Báo cáo & Phản hồi</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; text-align: left; background: none; border: none; box-shadow: none;">
                <tr style="background: none; border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-weight: 700; color: #4b5563; width: 120px; border: none;">Người gửi:</td>
                    <td id="modalSender" style="padding: 10px 0; color: #1f2937; border: none;"></td>
                </tr>
                <tr style="background: none; border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-weight: 700; color: #4b5563; border: none;">Liên hệ:</td>
                    <td id="modalContact" style="padding: 10px 0; color: #1f2937; border: none;"></td>
                </tr>
                <tr style="background: none; border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-weight: 700; color: #4b5563; border: none;">Ngày gửi:</td>
                    <td id="modalDate" style="padding: 10px 0; color: #1f2937; border: none;"></td>
                </tr>
                <tr style="background: none; border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-weight: 700; color: #4b5563; border: none;">Tiêu đề:</td>
                    <td id="modalSubject" style="padding: 10px 0; color: var(--primary); font-weight: 600; border: none;"></td>
                </tr>
            </table>
            
            <div style="font-weight: 700; color: #4b5563; font-size: 14px; margin-bottom: 8px;">Nội dung phản hồi:</div>
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; font-size: 15px; line-height: 1.6; color: #334155; white-space: pre-line; max-height: 250px; overflow-y: auto;" id="modalMessage">
            </div>
            
            <div style="text-align: right; margin-top: 25px;">
                <button type="button" onclick="closeReportModal()" style="background-color: #0f172a; color: white; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; transition: background-color 0.2s ease;">Đóng</button>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .report-modal-close:hover {
            color: #ef4444 !important;
        }
        .view-report-btn:hover {
            background-color: var(--border) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('reportModal');
            const closeBtn = document.querySelector('.report-modal-close');
            
            // Lắng nghe sự kiện click nút xem chi tiết
            document.querySelectorAll('.view-report-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const contact = this.getAttribute('data-contact');
                    const date = this.getAttribute('data-date');
                    const subject = this.getAttribute('data-subject');
                    // Lấy nội dung đầy đủ từ thẻ div ẩn kế bên
                    const message = this.nextElementSibling.textContent;
                    
                    document.getElementById('modalSender').textContent = name;
                    document.getElementById('modalContact').textContent = contact;
                    document.getElementById('modalDate').textContent = date;
                    document.getElementById('modalSubject').textContent = subject;
                    document.getElementById('modalMessage').textContent = message;
                    
                    modal.style.display = 'flex';
                });
            });
            
            // Đóng modal
            closeBtn.addEventListener('click', closeReportModal);
            
            // Đóng khi click ra ngoài
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeReportModal();
                }
            });
        });
        
        function closeReportModal() {
            document.getElementById('reportModal').style.display = 'none';
        }
    </script>
@endsection
