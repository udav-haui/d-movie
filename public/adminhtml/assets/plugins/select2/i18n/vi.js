$.fn.select2.amd.define('select2/i18n/vi', [], function () {
  // Vietnamese
  return {
    errorLoading: function () {
        return 'Không thể hiển thị kết quả.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Vui lòng xóa bớt ' + overChars + ' ký tự';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Vui lòng nhập thêm từ ' + remainingChars +
                    ' ký tự trở lên';

      return message;
    },
    loadingMore: function () {
      return 'Đang lấy thêm kết quả…';
    },
    maximumSelected: function (args) {
      var message = 'Chỉ có thể chọn được ' + args.maximum + ' lựa chọn';

      return message;
    },
    noResults: function () {
      return 'Không tìm thấy kết quả';
    },
    searching: function () {
      return 'Đang tìm…';
    },
    removeAllItems: function () {
      return 'Xóa tất cả các mục';
    }
  };
});
