package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Team(
    @Transient override var id: ByteArray,
    @SerializedName("key") val key: String,
    @SerializedName("number") val number: Int,
    @SerializedName("name") val name: String,
    @SerializedName("city") val city: String?,
    @SerializedName("state_province") val stateProvince: String?,
    @SerializedName("country") val country: String?,
    @SerializedName("rookie_year") val rookieYear: Int?,
    @SerializedName("facebook_url") val facebookUrl: String?,
    @SerializedName("instagram_url") val instagramUrl: String?,
    @SerializedName("twitter_url") val twitterUrl: String?,
    @SerializedName("youtube_url") val youtubeUrl: String?,
    @SerializedName("website_url") val websiteUrl: String?,
    @SerializedName("avatar_uri") val avatarUri: String?,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        name
}