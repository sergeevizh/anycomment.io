import React from 'react';
import AnyCommentComponent from "./AnyCommentComponent";
import footerLogo from '../img/mini-logo.svg'

/**
 * Display plugin copyright in the very bottom of comments.
 */
class CommentCopyright extends AnyCommentComponent {

    shouldComponentUpdate() {
        return false;
    }

    render() {
        const settings = this.getSettings();

        if (!settings.options.isCopyright) {
            return (null);
        }

        const logoSvg = <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
            <g fill="none" fill-rule="evenodd">
                <path fill="#44CDAC" d="M10.8 30L6 25.2v-9.6l4.8 4.8z"/>
                <path fill="#44CDAC" d="M14.078 25.08A9.6 9.6 0 1 0 6 15.6v9.48h8.078z"/>
                <path fill="#EC4568" d="M10.8 20.4a9.6 9.6 0 1 1 9.6 9.6h-9.6v-9.6z"/>
                <path fill="#53146C"
                      d="M15.6 25.2a9.6 9.6 0 0 0 9.38-11.652 9.528 9.528 0 0 0-.628-1.9A9.565 9.565 0 0 0 20.4 10.8a9.57 9.57 0 0 0-6.788 2.812A9.57 9.57 0 0 0 10.8 20.4v4.8h4.8z"/>
            </g>
        </svg>;

        return (
            <footer className="anycomment anycomment-copy-footer">
                {logoSvg} <a href="https://anycomment.io"
                             target="_blank">{settings.i18.footer_copyright}</a>
            </footer>
        );
    }
}

export default CommentCopyright;