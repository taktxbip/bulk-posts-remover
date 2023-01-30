class Modal {
  constructor(args, submitCallback, data) {
    const { postType, dateFrom, dateTo, found } = { ...args };
    this.postType = postType;
    this.dateFrom = dateFrom;
    this.dateTo = dateTo;
    this.found = found;
    this.submitCallback = submitCallback;
    this.data = data;

    this.insertHtml();
    this.events();
  }

  events() {
    jQuery('body .bpr-modal').on('click', '.bpr-modal-close, .action-cancel', () => this.closeModal());
    jQuery('body .bpr-modal').on('click', '.action-submit', () => {
      this.closeModal();
      this.submitCallback(this.data);
    });
  }

  closeModal() {
    jQuery('body .bpr-modal.show').removeClass('show');
  }

  submit(callback, data) {
    callback(data);
    this.closeModal();
  }

  insertHtml() {
    jQuery('body .bpr-modal').remove();

    const footer = ` <div class="bpr-modal-footer">
                            <button class="button button-primary button-large action-submit">Remove</button>
                            <button class="button button-secondary button-large action-cancel">Cancel</button>
                        </div>`;

    const entity = this.found === 1 ? 'entity' : 'entities';

    let html = `<div class="bpr-modal show">
                        <div class="bpr-modal-container">
                            <span class="bpr-modal-close">
                                <svg role="img" class="icon-close"><use xlink:href="#icon-close"></use></svg>
                            </span>
                            <div class="bpr-modal-title">
                                <h2>Found: ${this.found} ${entity}</h2>
                            </div>
                            <div class="bpr-modal-body">
                                <h4>Filters</h4>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Post type:</td>
                                            <td>${this.postType}</td>
                                        </tr>
                                        ${this.dateFrom && `<tr><td>Date from:</td><td>${this.dateFrom}</td></tr>`}
                                        ${this.dateTo && `<tr><td>Date to:</td><td>${this.dateTo}</td></tr>`}
                                    </tbody>
                                </table>
                                ${this.found > 0 ? `<p class="attention">Remove found ${entity}? This action can\'t be undone</p>` : ''}
                                ${this.postType === 'attachment' ? `<p class="attention">Warning! Attachments files (jpeg, png and gif) will be removed from server as well.</p>` : ''}
                            </div>
                            ${this.found > 0 ? footer : ''}
                        </div>
                    </div>`;
    const body = jQuery('body');
    body.append(html);
  }
}

export default Modal;