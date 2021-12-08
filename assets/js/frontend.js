+(function ($) {
  var pending_ajax = false;

  var weiDocs = {
    initialize: function () {
      $('.weidocs-feedback-wrap').on('click', 'a', this.feedback);
      $('#top-search-form .dropdown-menu').on('click', 'a', this.searchForm);
      $('a.weidocs-print-article').on('click', this.printArticle);

      // sidebar toggle
      $('ul.doc-nav-list .page_item_has_children').on(
        'click',
        '.weidocs-caret',
        function (event) {
          event.preventDefault();
          var self = $(this),
            parent = self.closest('.page_item');

          if (parent.hasClass('wd-state-closed')) {
            parent.removeClass('wd-state-closed').addClass('wd-state-open');
          } else {
            parent.removeClass('wd-state-open').addClass('wd-state-closed');
          }
        }
      );

      // modal
      $('a#weidocs-stuck-modal').on('click', this.showModal);
      $('a#weidocs-modal-close').on('click', this.closeModal);
      $('#weidocs-modal-backdrop').on('click', this.closeModal);
      $('form#weidocs-contact-modal-form').on('submit', this.contactHelp);
    },

    feedback: function (e) {
      e.preventDefault();

      // return if any request is in process already
      if (pending_ajax) {
        return;
      }

      pending_ajax = true;

      var self = $(this),
        wrap = self.closest('.weidocs-feedback-wrap'),
        data = {
          post_id: self.data('id'),
          type: self.data('type'),
          action: 'weidocs_ajax_feedback',
          _wpnonce: weiDocs_Vars.nonce,
        };

      wrap.append(
        '&nbsp; <i class="weidocs-icon weidocs-icon-refresh weidocs-icon-spin"></i>'
      );
      $.post(weiDocs_Vars.ajaxurl, data, function (resp) {
        wrap.html(resp.data);

        pending_ajax = false;
      });
    },

    searchForm: function (e) {
      e.preventDefault();

      var param = $(this).attr('href').replace('#', '');
      var concept = $(this).text();

      $('#top-search-form span#search_concept').text(concept);
      $('.input-group #search_param').val(param);
    },

    printArticle: function (e) {
      e.preventDefault();

      var article = $(this).closest('article');

      var mywindow = window.open('', 'my div', 'height=600,width=800');
      mywindow.document.write('<html><head><title>Print Article</title>');
      mywindow.document.write(
        '<link rel="stylesheet" href="' +
          weiDocs_Vars.style +
          '" type="text/css" media="all" />'
      );
      mywindow.document.write('</head><body >');
      mywindow.document.write(article.html());
      mywindow.document.write(
        '<div class="powered-by">' + weiDocs_Vars.powered + '</div>'
      );
      mywindow.document.write('</body></html>');

      mywindow.document.close(); // necessary for IE >= 10
      mywindow.focus(); // necessary for IE >= 10

      setTimeout(function () {
        // mywindow.print();
        // mywindow.close();
      }, 2000);

      return true;
    },

    showModal: function (e) {
      e.preventDefault();

      $('#weidocs-modal-backdrop').show();
      $('#weidocs-contact-modal').show();
      $('body').addClass('weidocs-overflow-hidden');
    },

    closeModal: function (e) {
      e.preventDefault();

      $('#weidocs-modal-backdrop').hide();
      $('#weidocs-contact-modal').hide();
      $('body').removeClass('weidocs-overflow-hidden');
    },

    contactHelp: function (e) {
      e.preventDefault();

      var self = $(this),
        submit = self.find('input[type=submit]'),
        body = self.closest('.weidocs-modal-body'),
        data = self.serialize() + '&_wpnonce=' + weiDocs_Vars.nonce;

      submit.prop('disabled', true);

      $.post(weiDocs_Vars.ajaxurl, data, function (resp) {
        if (resp.success === false) {
          submit.prop('disabled', false);
          $('#weidocs-modal-errors', body)
            .empty()
            .append(
              '<div class="weidocs-alert weidocs-alert-danger">' +
                resp.data +
                '</div>'
            );
        } else {
          body
            .empty()
            .append(
              '<div class="weidocs-alert weidocs-alert-success">' +
                resp.data +
                '</div>'
            );
        }
      });
    },
  };

  $(function () {
    weiDocs.initialize();
  });

  // initialize anchor.js
  anchors.options = {
    icon: '#',
  };
  anchors.add(
    '.weidocs-single-content .entry-content > h2, .weidocs-single-content .entry-content > h3'
  );
})(jQuery);
