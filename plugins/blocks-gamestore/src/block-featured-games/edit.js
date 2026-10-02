import { __ } from '@wordpress/i18n';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
    const { title, description, count } = attributes;

    return (
        <>
            <InspectorControls>
                <PanelBody title={ __( 'Featured Games settings', 'blocks-gamestore' ) }>
                    <RangeControl
                        label={ __( 'Number of games', 'blocks-gamestore' ) }
                        min={ 1 }
                        max={ 50 }
                        value={ count }
                        onChange={ ( value ) => setAttributes( { count: value } ) }
                        withInputField
                    />
                </PanelBody>
            </InspectorControls>
            <div { ...useBlockProps( { className: 'alignfull' } ) }>
                <div className="featured-games-inner wrapper">
                    <RichText
                        tagName="h2"
                        className="featured-games-title"
                        value={ title }
                        onChange={ ( value ) => setAttributes( { title: value } ) }
                        placeholder={ __( 'Featured Games', 'blocks-gamestore' ) }
                        allowedFormats={ [ 'core/bold', 'core/italic' ] }
                    />
                    <RichText
                        tagName="p"
                        className="featured-games-description"
                        value={ description }
                        onChange={ ( value ) => setAttributes( { description: value } ) }
                        placeholder={ __( 'Add a description…', 'blocks-gamestore' ) }
                        allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
                    />
                </div>
                <ServerSideRender block="blocks-gamestore/block-featured-games" attributes={ attributes } />
            </div>
        </>
    );
}
