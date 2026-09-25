import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const {title, description, link, video, image, mediaMode, linkAnchor, slides} = attributes;
	const activeMedia = (mediaMode === 'image' && image) || (!video && image) ? 'image' : 'video';
	return (
		<div { ...useBlockProps.save() }>
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
				<RichText.Content
					tagName='h1'
					className='hero-title'
					value={title}
				/>
				<RichText.Content
					tagName='p'
					className='hero-description'
					value={description}
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
	);
}
