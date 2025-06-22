// File: assets/js/speaker-block.js
const { registerBlockType } = wp.blocks;
const { useSelect } = wp.data;
const { useEntityRecords } = wp.coreData;
const { createElement, Fragment } = wp.element;
const { Spinner } = wp.components;

registerBlockType('tyler-child/speakers-list', {
    title: 'Speakers List',
    icon: 'microphone',
    category: 'widgets',
    
    edit: function() {
        const { records: speakers, isResolving } = useEntityRecords('postType', 'speaker', {
            per_page: -1,
            status: 'publish'
        });

        if (isResolving) {
            return createElement(Spinner);
        }

        if (!speakers || speakers.length === 0) {
            return createElement('p', {}, 'No speakers found.');
        }

        return createElement('div', { className: 'speakers-preview' },
            speakers.map(speaker => 
                createElement('div', { 
                    key: speaker.id,
                    className: 'speaker-preview-item'
                }, `${speaker.title.rendered} - Preview`)
            )
        );
    },

    save: function() {
        // Return null for dynamic block
        return null;
    }
});