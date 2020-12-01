package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ByteArrayTable
import org.jetbrains.exposed.sql.Op
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.SqlExpressionBuilder
import org.jetbrains.exposed.sql.Table
import org.jetbrains.exposed.sql.statements.InsertStatement

abstract class ByteArrayTable<T: ByteArrayTable>(name: String = "", columnName: String = "id") : Table(name) {
    val id = binary(columnName, 16).primaryKey()

    abstract fun fromResultRow(resultRow: ResultRow): T
    abstract fun insert(obj: T): InsertStatement<Number>
    abstract fun update(obj: T, where: (SqlExpressionBuilder.()-> Op<Boolean>)? = null): Int

    /**
     * Inserts or updated a record in the database
     * @param obj [T] object to insert or update
     * @return [Boolean] if successful
     */
    fun upsert(obj: T, where: (SqlExpressionBuilder.()-> Op<Boolean>)? = null): Boolean {
        if (update(obj, where) < 1) {
            insert(obj)

            return true
        }

        return true
    }

    /**
     * Inserts or updated a record in the database
     * @param obj [T] object to insert or update
     * @return [Boolean] if successful
     */
    fun upsertAll(objList: List<T>, where: (SqlExpressionBuilder.()-> Op<Boolean>)? = null): HashMap<ByteArray, Boolean> {
        val resultSet: HashMap<ByteArray, Boolean> = hashMapOf()

        objList.forEach { obj ->
            if (update(obj, where) < 1) {
                insert(obj)

                resultSet[obj.id] = true
            }

            resultSet[obj.id] = true
        }

        return resultSet
    }
}