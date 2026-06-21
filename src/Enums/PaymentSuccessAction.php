<?php

namespace Gopay\GopayUi\Enums;

enum PaymentSuccessAction: string
{
    case REFRESH_PAGE = 'refresh_page';
    case GO_TO_URL = 'go_to_url';
}
