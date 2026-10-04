import { createRoot } from 'react-dom/client';
import PaymentPage from './PaymentPage.jsx';

const rootElement = document.getElementById('root');

if (!rootElement) {
  throw new Error('Payment page mount target #payment-root was not found.');
}

const paymentConfig = window.PAYMENT_PAGE_CONFIG || {};

createRoot(rootElement).render(
  <PaymentPage
    {...paymentConfig}
    autoRedirectOnInvalidToken={paymentConfig.autoRedirectOnInvalidToken ?? false}
  />,
);
