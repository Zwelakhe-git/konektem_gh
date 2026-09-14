<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Хлебные крошки -->
        <nav class="mb-8" style="margin-bottom: 35px;">
            <ol class="flex items-center space-x-2 text-sm text-gray-600" style="justify-content: space-evenly;">
                <li class="text-purple-600 font-medium">Service order</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Основная форма -->
            <div class="lg:col-span-2" >
                <div class="bg-white rounded-2xl shadow-xl p-8 form-section-custom" style="padding: 20px">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-concierge-bell text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Service Order</h2>
                            <p class="text-gray-600">Please fill in the form and we will contact you</p>
                        </div>
                    </div>

                    <form id="serviceOrderForm" class="space-y-6">
                        <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                        
                        <!-- Информация о услуге -->
                        <div class="bg-blue-50 rounded-xl p-4 mb-6">
                            <div class="flex items-start space-x-4">
                                <?php if($service['image_url']){?>
                                <img src="<?= $service['image_url'] ?>" 
                                        alt="<?= $service['name'] ?>" 
                                        class="w-20 h-20 rounded-lg object-cover shadow">
                                <?php }else{?>
                                <div class="w-20 h-20 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-concierge-bell text-purple-600 text-2xl"></i>
                                </div>
                                <?php }?>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-800"><?= $service['name'] ?></h3>
                                    <p class="text-gray-600 text-sm mt-1 line-clamp-2"><?= $service['description'] ?></p>
                                    <?php if((int)$service['price'] > 0){ ?>
                                    <div class="mt-2">
                                        <span class="text-2xl font-bold text-purple-600">$<?= $service['price'] ?></span>
                                        <span class="text-gray-500 text-sm ml-2">от</span>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

                        <!-- Информация о клиенте -->
                        <div class="form-group-custom">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-circle text-purple-600 mr-2"></i>
                                Contact information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Surname Name *
                                    </label>
                                    <input type="text" id="full_name" name="full_name" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom"
                                            placeholder="Иванов Иван Иванович">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone *
                                    </label>
                                    <input type="tel" id="phone" name="phone" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom"
                                            placeholder="phone number">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email *
                                </label>
                                <input type="email" id="email" name="email" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom"
                                        placeholder="example@email.com">
                            </div>
                        </div>

                        <!-- Дополнительная информация -->
                        <div class="form-group-custom">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                                Additional information
                                <span class="text-gray-400 text-xs font-normal ml-1">(необязательно)</span>
                            </label>
                            <textarea id="message" name="message" rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom resize-none"
                                        placeholder="write the details of the order, date and requirements..."></textarea>
                        </div>

                        <!-- Соглашения -->
                        <div class="form-group-custom">
                            <div class="space-y-3">
                                <label class="flex items-start space-x-3">
                                    <input type="checkbox" name="privacy_policy" required
                                            class="mt-1 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                    <span class="text-sm text-gray-600">
                                        Give consent to processing of personal information 
                                        <a href="/privacy" class="text-purple-600 hover:underline">политикой конфиденциальности</a>
                                    </span>
                                </label>
                                <label class="flex items-start space-x-3">
                                    <input type="checkbox" name="contact_agree" required
                                            class="mt-1 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                    <span class="text-sm text-gray-600">
                                        Agree to receive notifications through the given contacts
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Кнопка отправки -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:from-purple-700 hover:to-blue-700 transition duration-200 flex items-center justify-center payment-btn-custom shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Send order
                        </button>

                        <div id="formMessage" class="hidden"></div>
                    </form>
                </div>
            </div>

            <!-- Боковая панель -->
            <div class="lg:col-span-1" style="padding: 20px;">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-6 sidebar-enhanced"
                style="padding: 20px;
                margin: 0px auto;
                "
                >
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        How it works?
                    </h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <span class="font-bold">1</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Заполните форму</p>
                                <p class="text-sm text-gray-600">Укажите контактные данные и детали заказа</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <span class="font-bold">2</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Мы связываемся с вами</p>
                                <p class="text-sm text-gray-600">В течение 1-2 часов в рабочее время</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <span class="font-bold">3</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Details</p>
                                <p class="text-sm text-gray-600">Discuss deadline, costs and requirements</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <span class="font-bold">4</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">We complete the job</p>
                                <p class="text-sm text-gray-600">We immediately start doing the job</p>
                            </div>
                        </div>
                    </div>

                    <!-- Контактная информация -->
                    <div class="border-t pt-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Contacts for questions</h4>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone text-purple-600 mr-2 w-4"></i>
                                <span><?= $settings['contact']['phone'] ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope text-purple-600 mr-2 w-4"></i>
                                <span><?= $settings['email']['site_email'] ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock text-purple-600 mr-2 w-4"></i>
                                <span>Mon-Fir </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>