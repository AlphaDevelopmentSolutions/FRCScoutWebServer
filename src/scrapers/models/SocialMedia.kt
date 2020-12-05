package com.alphadevelopmentsolutions.scrapers.models

import com.google.gson.annotations.SerializedName

class SocialMedia(
    @SerializedName("type") val type: String,
    @SerializedName("foreign_key") val foreignKey: String
) {
    companion object {
        val youtube: String = "youtube"
        val cdphotothread: String = "cdphotothread"
        val imgur: String = "imgur"
        val facebook_profile: String = "facebook-profile"
        val youtube_channel: String = "youtube-channel"
        val twitter_profile: String = "twitter-profile"
        val github_profile: String = "github-profile"
        val instagram_profile: String = "instagram-profile"
        val periscope_profile: String = "periscope-profile"
        val grabcad: String = "grabcad"
        val instagram_image: String = "instagram-image"
        val external_link: String = "external-link"
        val avatar: String = "avatar"
    }
}