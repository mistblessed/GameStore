import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaPlaceholder } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, TextareaControl } from '@wordpress/components';
import './editor.scss';
import { useState } from 'react';
import { Button } from '@wordpress/components';

const SlideItem = ({index, slide, onImageChange, onRemove}) => {
	return (
		<div className='slide-item'>
			<div className='slide-item-image'>
				<p>Light Version  Logo</p>
				{slide.lightImage && <div className='image-box'> <img src={slide.lightImage} alt="Slide Image" /> </div>}
				<MediaPlaceholder 
					icon="format-image"
					onSelect={(media) => onImageChange(media.url, index, "lightImage")}
					onSelectURL={(url) => onImageChange(url, index, "lightImage")}
					labels={{
						title: 'Slide Light Image',
						instructions: 'Upload an image for the slide'
					}}
					accept='image/*'
					allowedTypes={['image']}
					multiple={false}
				/>
			</div>
			<div className='slide-item-image'>
				<p>Dark Version  Logo</p>
				{slide.darkImage && <div className='image-box'> <img src={slide.darkImage} alt="Slide Image" /> </div>}
				<MediaPlaceholder 
					icon="format-image"
					onSelect={(media) => onImageChange(media.url, index, "darkImage")}
					onSelectURL={(url) => onImageChange(url, index, "darkImage")}
					labels={{
						title: 'Slide Dark Image',
						instructions: 'Upload an image for the slide'
					}}
					accept='image/*'
					allowedTypes={['image']}
					multiple={false}
				/>
			</div>
			<Button className='components-button is-destructive' onClick={() => onRemove(index)}>Remove</Button>
		</div>
	)
}

export default function Edit({ attributes, setAttributes}) {
	const {title, description, link, video, image, mediaMode, linkAnchor, slides: initialSlides } = attributes;
	const activeMedia = (mediaMode === 'image' && image) || (!video && image) ? 'image' : 'video';

	const [slides, setSlides] = useState(initialSlides || []);

	const onSlideChange = (updatedSlide, index) => {
		const updatedSlides = [...slides];
		updatedSlides[index] = updatedSlide;
		setSlides(updatedSlides);
		setAttributes({ slides: updatedSlides})
	}

	const addSlide = () => {
		const newSlide = { lightImage: '', darkImage: ''};
		const updateSlides = [...slides, newSlide];
		setSlides(updateSlides);
		setAttributes({ slides: updateSlides });
	}

	const removeSlide = (index) => {
		const updatedSlides = [...slides];
		updatedSlides.splice(index, 1);
		setSlides(updatedSlides);
		setAttributes({ slides: updatedSlides})
	}

	const handleImageChange = (url, index, imageType) => {
		const updatedSlides = { ...slides[index], [imageType]: url};
		onSlideChange(updatedSlides, index);
	}

	return (
		<>
		<InspectorControls>
			<PanelBody title='Hero Settings'>
				<TextControl
					label="Title"
					value={title}
					onChange={(title) => setAttributes({title})}
				/>
				<TextareaControl
					label="Description"
					value={description}
					onChange={(description) => setAttributes({description})}
				/>
				<TextControl
					label="Button URL"
					value={link}
					onChange={(link) => setAttributes({link})}
				/>
				<TextControl
					label="Button Value"
					value={linkAnchor}
					onChange={(linkAnchor) => setAttributes({linkAnchor})}
				/>
				{image && (
					<img src={image} alt="" style={{ width: '100%' }} />
				)}
				<MediaUpload
					onSelect={(media) => setAttributes({
						image: media.url,
						mediaMode: image || video ? activeMedia : 'image',
					})}
					allowedTypes={['image']}
					render={({ open }) => (
						<button className='components-button is-secondary' onClick={open}>
							{image ? 'Replace Image' : 'Select Image'}
						</button>
					)}
				/>
				{video && (
					<video controls muted src={video} width="100%" />
				)}
				<MediaUpload
					onSelect={(media) => setAttributes({
						video: media.url,
						mediaMode: image || video ? activeMedia : 'video',
					})}
					allowedTypes={['video']}
					render={({ open }) => (
						<button className='components-button is-secondary' onClick={open}>
							{video ? 'Replace Video' : 'Select Video'}
						</button>
					)}
				/>
				{image && video && (
				<SelectControl
					label="Show in Hero"
					value={activeMedia}
					options={[
						{ label: 'Video', value: 'video' },
						{ label: 'Image', value: 'image' },
					]}
					onChange={(mediaMode) => setAttributes({ mediaMode })}
				/>
				)}
			</PanelBody>
			<PanelBody title='Hero Slider'>
				{slides.map((slide, index) => (
					<SlideItem 
						key={index}
						index={index}
						slide={slide}
						onImageChange={handleImageChange}
						onRemove={removeSlide}
					/>
				))}
				<Button className='components-button is-primary' onClick={addSlide}>Add Slide</Button>
			</PanelBody>
		</InspectorControls>
		<div { ...useBlockProps() }>
			{activeMedia === 'image' && image ? (
				<img
					className="video-bg"
					src={image}
					alt=""
					style={{ width: '100%', height: '100%', objectFit: 'cover' }}
				/>
			) : video && (
				<video
					key={video}
					className="video-bg"
					src={video}
					autoPlay
					loop
					muted
					playsInline
					width="100%"
					height="100%"
				/>
			)}
			<div className='hero-mask'></div>
			<div className='hero-content'>
				<RichText
					tagName='h1'
					className='hero-title'
					value={title}
					onChange={(title) => setAttributes({title})}
				/>
				<RichText
					tagName='p'
					className='hero-description'
					value={description}
					onChange={(description) => setAttributes({description})}
				/>
				<a href={link} className='hero-button shadow'>{linkAnchor}</a>
			</div>
			{slides && 
				<div className='hero-slider'>
					<div className='slider-container'>
						<div className='swiper-wrapper'>
							{slides.map((slide, index) => (
								<div key={index} className='swiper-slide slide-item'>
									<img src={slide.lightImage} alt="Logo" className='light-logo'/>
									<img src={slide.darkImage} alt="Logo" className='dark-logo'/>
								</div>
								)
							)}
						</div>
					</div>	
				</div>}
		</div>
		</>
	);
}
