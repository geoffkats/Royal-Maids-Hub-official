<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Determine whether a table already has a soft delete column.
     */
    protected function hasDeletedAtColumn(string $table): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        // Fast path for schema builders that already know the column state.
        if (Schema::hasColumn($table, 'deleted_at')) {
            return true;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $prefixedTable = DB::getTablePrefix() . $table;
            return !empty(DB::select("SHOW COLUMNS FROM `{$prefixedTable}` LIKE 'deleted_at'"));
        }

        if ($driver === 'sqlite') {
            return collect(DB::select("PRAGMA table_info({$table})"))
                ->contains(fn ($column) => $column->name === 'deleted_at');
        }

        if ($driver === 'pgsql') {
            return !empty(DB::select(
                "select column_name from information_schema.columns where table_name = ? and column_name = 'deleted_at'",
                [$table]
            ));
        }

        return Schema::hasColumn($table, 'deleted_at');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing soft delete columns to align models with persistence.
        if (!$this->hasDeletedAtColumn('maids')) {
            Schema::table('maids', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!$this->hasDeletedAtColumn('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!$this->hasDeletedAtColumn('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!$this->hasDeletedAtColumn('trainers')) {
            Schema::table('trainers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!$this->hasDeletedAtColumn('training_programs')) {
            Schema::table('training_programs', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!$this->hasDeletedAtColumn('evaluations')) {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!$this->hasDeletedAtColumn('crm_leads')) {
            Schema::table('crm_leads', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->hasDeletedAtColumn('maids')) {
            Schema::table('maids', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if ($this->hasDeletedAtColumn('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if ($this->hasDeletedAtColumn('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if ($this->hasDeletedAtColumn('trainers')) {
            Schema::table('trainers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if ($this->hasDeletedAtColumn('training_programs')) {
            Schema::table('training_programs', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if ($this->hasDeletedAtColumn('evaluations')) {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if ($this->hasDeletedAtColumn('crm_leads')) {
            Schema::table('crm_leads', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
