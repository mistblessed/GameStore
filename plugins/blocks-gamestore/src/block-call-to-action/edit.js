import { __, sprintf } from '@wordpress/i18n';
import { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

const imageFields = [
    [ 'icon', __( 'icon', 'blocks-gamestore' ) ],
    [ 'background', __( 'background', 'blocks-gamestore' ) ],
    [ 'character', __( 'character', 'blocks-gamestore' ) ],
];

export default function Edit( { attributes, setAttributes } ) {
    const fields = [
        [ 'buttonOneLabel', __( 'Game Store label', 'blocks-gamestore' ) ],
        [ 'buttonOneUrl', __( 'Game Store URL', 'blocks-gamestore' ) ],
        [ 'buttonTwoLabel', __( 'Services label', 'blocks-gamestore' ) ],
        [ 'buttonTwoUrl', __( 'Services URL', 'blocks-gamestore' ) ],
        [ 'buttonThreeLabel', __( 'Downloads label', 'blocks-gamestore' ) ],
        [ 'buttonThreeUrl', __( 'Downloads URL', 'blocks-gamestore' ) ],
    ];

    return (
        <>
            <InspectorControls>
                <PanelBody title={ __( 'Content', 'blocks-gamestore' ) }>
                    <TextControl
                        label={ __( 'Title', 'blocks-gamestore' ) }
                        value={ attributes.title }
                        onChange={ ( title ) => setAttributes( { title } ) }
                    />
                    <TextareaControl
                        label={ __( 'Description', 'blocks-gamestore' ) }
                        value={ attributes.description }
                        onChange={ ( description ) => setAttributes( { description } ) }
                    />
                </PanelBody>
                <PanelBody title={ __( 'Images', 'blocks-gamestore' ) } initialOpen={ false }>
                    { imageFields.map( ( [ key, label ] ) => (
                        <div className="call-to-action-image-control" key={ key }>
                            <MediaUploadCheck>
                                <MediaUpload
                                    allowedTypes={ [ 'image' ] }
                                    onSelect={ ( media ) => setAttributes( { [ key ]: media.url } ) }
                                    render={ ( { open } ) => (
                                        <Button variant="secondary" onClick={ open }>
                                            { sprintf( __( 'Replace %s', 'blocks-gamestore' ), label ) }
                                        </Button>
                                    ) }
                                />
                            </MediaUploadCheck>
                            { attributes[ key ] && (
                                <Button variant="tertiary" onClick={ () => setAttributes( { [ key ]: '' } ) }>
                                    { __( 'Use default', 'blocks-gamestore' ) }
                                </Button>
                            ) }
                        </div>
                    ) ) }
                    <TextControl
                        label={ __( 'Character image alt text', 'blocks-gamestore' ) }
                        value={ attributes.characterAlt }
                        onChange={ ( characterAlt ) => setAttributes( { characterAlt } ) }
                    />
                </PanelBody>
                <PanelBody title={ __( 'Buttons', 'blocks-gamestore' ) } initialOpen={ false }>
                    { fields.map( ( [ key, label ] ) => (
                        <TextControl
                            key={ key }
                            label={ label }
                            help={ key === 'buttonOneUrl' ? __( 'Uses the WooCommerce shop when empty.', 'blocks-gamestore' ) : undefined }
                            value={ attributes[ key ] || '' }
                            onChange={ ( value ) => setAttributes( { [ key ]: value } ) }
                        />
                    ) ) }
                </PanelBody>
            </InspectorControls>
            <div { ...useBlockProps( { className: 'alignfull' } ) }>
                <ServerSideRender block="blocks-gamestore/block-call-to-action" attributes={ attributes } />
            </div>
        </>
    );
}
