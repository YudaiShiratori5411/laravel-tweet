@if(preg_match('/^.*\.(jpeg|jpg|png|gif)$/i', $media_path))
    <img src="{{ Storage::url($media_path) }}" alt="メディア" width="250">
@elseif(preg_match('/^.*\.(mp4|mov|avi)$/i', $media_path))
    <video width="250" controls>
        <source src="{{ Storage::url($media_path) }}" type="video/mp4">
        お使いのブラウザではビデオ再生をサポートしていません。
    </video>
@endif
