<div id="content-consent-modal" class="modal fade" tabindex="-1" role="dialog"
     data-backdrop="static"
     aria-labelledby="content-consent-modal_modalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="color: black;">
            <div class="modal-header">
                <h4 class="modal-title" id="content-consent-modal_modalLabel">
                    {{ \Settings::get('utility_content_consent_popup_title') }}
                </h4>
            </div>
            <div class="modal-body" id="modal-body-content-consent-modal">
                {!! \Settings::get('utility_content_consent_popup_content') !!}
            </div>
            <div class="modal-footer">
                <a href="{{ url('utilities/content-consent-answer/accepted') }}" class="btn btn-success">
                    {!! \Settings::get('utility_content_consent_accept_button_text') !!}
                </a>
                <a href="{{ url('utilities/content-consent-answer/rejected') }}" class="btn btn-warning">
                    {!! \Settings::get('utility_content_consent_reject_button_text') !!}
                </a>
            </div>
        </div>
    </div>
</div>
