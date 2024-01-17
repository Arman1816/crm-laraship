<?php

use Illuminate\Support\Facades\DB as DBAlias;

DBAlias::statement("ALTER TABLE invoices MODIFY status ENUM('paid', 'pending', 'failed', 'cancelled') NOT NULL");
