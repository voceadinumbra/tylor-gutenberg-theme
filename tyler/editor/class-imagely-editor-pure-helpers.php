<?php

if (!class_exists('Imagely_Editor_Pure_Helpers')) {

    /**
     * Pure functions used for manipulating the editor
     */
    class Imagely_Editor_Pure_Helpers
    {
        /**
         * Determines if a post has page builder content
         * 
         * @param WP_Post $post
         * @return bool
         */
        public function has_tesla_page_builder_content($post)
        {
            return stripos($post->post_content, '[efcb-section-') !== FALSE;
        }

        /**
         * Determines if a post has VC content
         */
        public function has_vc_content($post)
        {
            return stripos($post->post_content, '[vc_') !== false;
        }

        /**
         * Transforms the edit post url to one used to open the post with the classic editor
         * 
         * @param string $url
         * @return string
         */
        public function to_classic_edit_url($url)
        {
            return (strpos($url, 'classic-editor') !== FALSE)
                ? $url
                : add_query_arg('classic-editor', '', $url);
        }

        /**
         * Given the $_REQUEST array, determines if the classic editor has been requested
         * @param array $request
         * @return bool
         */
        public function is_classic_editor_requested($request)
        {
            return array_key_exists('classic-editor', $request);
        }
    }

}