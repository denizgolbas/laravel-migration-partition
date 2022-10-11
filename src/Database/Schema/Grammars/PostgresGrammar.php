<?php

namespace ORPTech\MigrationPartition\Database\Schema\Grammars;

use Illuminate\Support\Fluent;
use ORPTech\MigrationPartition\Database\Schema\Blueprint;
use \Illuminate\Database\Schema\Grammars\PostgresGrammar as IlluminatePostgresGrammar;

class PostgresGrammar extends IlluminatePostgresGrammar
{
    /**
     * Compile a create table command with its range partitions.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileCreateRangePartitioned(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf('create table %s (%s) partition by range (%s)',
            $this->wrapTable($blueprint),
            sprintf('%s, %s', implode(', ', $this->getColumns($blueprint)), sprintf('primary key (%s, %s)', $blueprint->pkCompositeOne, $blueprint->pkCompositeTwo)),
            $blueprint->rangeKey
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }
    /**
     * Compile a create table partition command for a range partitioned table.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileInitRangePartition(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf('create table %s_%s partition of %s for values from (\'%s\') to (\'%s\')',
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->subfixForPartition,
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->startDate,
            $blueprint->endDate
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }

    /**
     * Compile a create table command with its list partitions.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileCreateListPartitioned(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf('create table %s (%s) partition by list(%s)',
            $this->wrapTable($blueprint),
            sprintf('%s, %s', implode(', ', $this->getColumns($blueprint)), sprintf('primary key (%s, %s)', $blueprint->pkCompositeOne, $blueprint->pkCompositeTwo)),
            $blueprint->listPartitionKey
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }
    /**
     * Compile a create table partition command for a list partitioned table.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileInitListPartition(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf('create table %s_%s partition of %s for values in (\'%s\')',
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->subfixForPartition,
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->listPartitionValue,
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }

    /**
     * Compile a create table command with its hash partitions.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileCreateHashPartitioned(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf('create table %s (%s) partition by hash(%s)',
            $this->wrapTable($blueprint),
            sprintf('%s, %s', implode(', ', $this->getColumns($blueprint)), sprintf('primary key (%s, %s)', $blueprint->pkCompositeOne, $blueprint->pkCompositeTwo)),
            $blueprint->hashPartitionKey
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }
    /**
     * Compile a create table partition command for a hash partitioned table.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileInitHasPartition(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf('create table %s_%s partition of %s for values with (modulus %s, remainder %s)',
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->subfixForPartition,
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->hashModulus,
            $blueprint->hashRemainder,
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }

    /**
<<<<<<< Updated upstream
=======
     * Compile a create table partition command for a hash partitioned table.
     *
     * @param  Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return array
     */
    public function compileAttachHashPartition(Blueprint $blueprint, Fluent $command)
    {
        return sprintf('alter table %s partition of %s for values with (modulus %s, remainder %s)',
            str_replace("\"", "", $this->wrapTable($blueprint)),
            $blueprint->partitionTableName,
            $blueprint->hashModulus,
            $blueprint->hashRemainder,
        );
    }

    /**
     * Get partition tables for a particular partitioned table
     * @param  string  $table
     * @return string
     */
    public function compileGetPartitions(string $table)
    {
        return  sprintf("SELECT inhrelid::regclass as tables
            FROM   pg_catalog.pg_inherits
            WHERE  inhparent = '%s'::regclass;",
            $table,
        );
    }

    /**
>>>>>>> Stashed changes
     * Get All Range Partitioned Tables
     * @return string
     */
    public function compileGetAllRangePartitionedTables()
    {

        return "select pg_class.relname as tables from pg_class inner join pg_partitioned_table on pg_class.oid = pg_partitioned_table.partrelid where pg_partitioned_table.partstrat = 'r';";
    }
}
