<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\CustomFields\Repositories;

use FI\Modules\CustomFields\Models\CustomField;
use Schema;

class CustomFieldRepository
{
    public function all()
    {
        return CustomField::all();
    }

    public function paginate()
    {
        return CustomField::sortable(['table_name' => 'asc', 'field_label' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function find($id)
    {
        return CustomField::find($id);
    }

    public function getByTable($table)
    {
        return CustomField::forTable($table)->get();
    }

    public function create($input)
    {
        return CustomField::create($input);
    }

    public function update($input, $id)
    {
        $customField = CustomField::find($id);

        $customField->fill($input);

        $customField->save();

        return $customField;
    }

    public function delete($id)
    {
        CustomField::destroy($id);
    }

    /**
     * Obtains the new column name (incremental) to add for a custom field.
     *
     * @param  string $tableName
     * @return string
     */
    public function getNextColumnName($tableName)
    {
        $currentColumn = CustomField::where('table_name', '=', $tableName)->orderBy('id', 'DESC')->take(1)->first();

        if (!$currentColumn)
        {
            return 'column_1';
        }
        else
        {
            $column = explode('_', $currentColumn->column_name);

            return $column[0] . '_' . ($column[1] + 1);
            exit;
        }
    }

    public function createCustomColumn($tableName, $columnName, $fieldType)
    {
        if (substr($tableName, -7) <> '_custom')
        {
            $tableName = $tableName . '_custom';
        }

        Schema::table($tableName, function ($table) use ($columnName, $fieldType)
        {
            if ($fieldType == 'textarea')
            {
                $table->text($columnName)->nullable();
            }
            else
            {
                $table->string($columnName)->nullable();
            }

        });
    }

    public function deleteCustomColumn($tableName, $columnName)
    {
        if (substr($tableName, -7) <> '_custom')
        {
            $tableName = $tableName . '_custom';
        }

        if (Schema::hasColumn($tableName, $columnName))
        {
            Schema::table($tableName, function ($table) use ($columnName)
            {
                $table->dropColumn($columnName);
            });
        }
    }
}