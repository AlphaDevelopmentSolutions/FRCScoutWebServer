package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.User
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object UserTable : ModifyTrackedTable<User>("users") {
    var firstName = varchar("first_name", 30)
    var lastName = varchar("last_name", 30)
    var email = varchar("email", 100)
    var username = varchar("username", 20)
    var password = varchar("password", 100)
    var description = varchar("description", 300).nullable()
    var avatarUri = varchar("avatar_uri", 100).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        User(
            resultRow[id],
            resultRow[firstName],
            resultRow[lastName],
            resultRow[email],
            resultRow[username],
            resultRow[description],
            resultRow[avatarUri],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: User) =
        insert {
            it[id] = obj.id
            it[firstName] = obj.firstName
            it[lastName] = obj.lastName
            it[email] = obj.email
            it[username] = obj.username
            it[password] = obj.password ?: ""
            it[description] = obj.description
            it[avatarUri] = obj.avatarUri
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: User) =
        update({ UserTable.id eq obj.id }) {
            it[firstName] = obj.firstName
            it[lastName] = obj.lastName
            it[email] = obj.email
            it[username] = obj.username
            it[description] = obj.description
            it[avatarUri] = obj.avatarUri
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}