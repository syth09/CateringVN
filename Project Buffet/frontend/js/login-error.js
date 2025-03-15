// <!-- Hiển thị thông báo lỗi từ PHP -->
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get("error");
if (error) {
  document.getElementById("error-message").innerText = error;
}
