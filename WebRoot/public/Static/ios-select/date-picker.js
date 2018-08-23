$(function () {
  $.fn.datePicker = function (options) {
    // 初始化时间
    var now = new Date();
    var nowYear = now.getFullYear();
    var nowMonth = now.getMonth() + 1;
    var nowDate = now.getDate();

    var setting = {
      change: $.noop
    }
    setting = $.extend(setting, options);

    // 数据初始化
    function formatYear(nowYear) {
      var arr = [];
      for (var i = nowYear - 25; i <= nowYear + 25; i++) {
        arr.push({
          id: i + '',
          value: i + '年'
        });
      }
      return arr;
    }
    function formatMonth() {
      var arr = [];
      for (var i = 1; i <= 12; i++) {
        arr.push({
          id: i + '',
          value: i + '月'
        });
      }
      return arr;
    }
    function formatDate(count) {
      var arr = [];
      for (var i = 1; i <= count; i++) {
        arr.push({
          id: i + '',
          value: i + '日'
        });
      }
      return arr;
    }
    var yearData = function (callback) {
      callback(formatYear(nowYear))
    }
    var monthData = function (year, callback) {
      callback(formatMonth());
    };
    var dateData = function (year, month, callback) {
      if (/^(1|3|5|7|8|10|12)$/.test(month)) {
        callback(formatDate(31));
      }
      else if (/^(4|6|9|11)$/.test(month)) {
        callback(formatDate(30));
      }
      else if (/^2$/.test(month)) {
        if (year % 4 === 0 && year % 100 !== 0 || year % 400 === 0) {
          callback(formatDate(29));
        }
        else {
          callback(formatDate(28));
        }
      }
      else {
        throw new Error('month is illegal');
      }
    };

    $(this).bind('click', function () {
      var me = $(this);
      var vals = me.val() ? me.val().replace(/-0/g, '-').split('-') : [nowYear, nowMonth, nowDate];

      var iosSelect = new IosSelect(3,
        [yearData, monthData, dateData],
        {
          title: '',
          itemHeight: 35,
          oneLevelId: vals[0],
          twoLevelId: vals[1],
          threeLevelId: vals[2],
          callback: function (year, month, day) {
            var value = year.id + (month.id < 10 ? '-0' : '-') + month.id + (day.id < 10 ? '-0' : '-') + day.id;
            if (setting.change && setting.change.call(me, value) !== false) {
              me.val(value);
            }
          }
        });
    });
  }
})